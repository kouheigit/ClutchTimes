class Application < ApplicationRecord
  belongs_to :user
  belongs_to :job

  enum status: { pending: 0, reviewing: 1, accepted: 2, rejected: 3, withdrawn: 4 }

  validates :status, presence: true
  validate :no_duplicate_application, on: :create

  scope :active, -> { where.not(status: :withdrawn) }

  def withdrawable?
    pending? || reviewing?
  end

  private

  def no_duplicate_application
    if Application.exists?(user: user, job: job, status: Application.statuses.except("withdrawn").keys)
      errors.add(:base, "すでにこの求人に応募しています")
    end
  end
end
