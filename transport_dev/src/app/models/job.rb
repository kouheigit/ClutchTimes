class Job < ApplicationRecord
  belongs_to :company
  belongs_to :area
  has_many :applications, dependent: :destroy
  has_and_belongs_to_many :license_types

  enum employment_type: { full_time: 0, part_time: 1, contract: 2, temporary: 3 }
  enum salary_type: { monthly: 0, hourly: 1, annual: 2 }
  enum status: { draft: 0, published: 1, closed: 2 }

  validates :title, presence: true
  validates :description, presence: true
  validates :employment_type, presence: true
  validates :salary_type, presence: true
  validates :status, presence: true

  scope :published, -> { where(status: :published) }
  scope :active, -> { published.where("expires_at IS NULL OR expires_at > ?", Time.current) }
  scope :expired, -> { published.where("expires_at <= ?", Time.current) }

  scope :search, ->(params) {
    result = all
    result = result.where(area_id: params[:area_id]) if params[:area_id].present?
    result = result.where(employment_type: params[:employment_type]) if params[:employment_type].present?
    result = result.where("salary_min >= ?", params[:salary_min]) if params[:salary_min].present?
    if params[:license_type_ids].present?
      result = result.joins(:license_types).where(license_types: { id: params[:license_type_ids] }).distinct
    end
    if params[:keyword].present?
      result = result.where("title LIKE :q OR description LIKE :q", q: "%#{params[:keyword]}%")
    end
    result
  }
end
