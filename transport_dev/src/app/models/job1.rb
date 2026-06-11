class Job < ApplicationRecord

    belongs_to :company
    belongs_to :area

    has_many :applications, dependent: :destroy
    has_and_belongs_to_many :license_types
    
    enum :employment_type, {
        full_time: 0,
        part_time: 1,
        contract: 2,
        temporary: 3
    }

    enum :salary_type,{
        full_time: 0,
        part_time: 1,
        contract: 2,
        temporary: 3
    }

    enum :status, {
        monthly: 0,
        hourly: 1,
        annual: 2,
    }

    enum :status,{
        draft: 0
        published: 1,
        closed: 2
    }, scopes: false

    validates :title, presence: true
    validates :description, presence: true
    validates :employment_type. presence: true
    validates :salary_type, presence: true
    validates :status, presence: true
  
    scope :published, -> { where(status: statuses[:published]) }
    scope :active, -> { published.where("expires_at IS NULL OR expires_at > ?", Time.current) }
    scope :expired, ->{ published.where("expires_at <= ?", Time.current) } 

    scope :search, lambda { |params| 
    jobs = all
    
    if params[:area_id].present?
        jobs = jobs.where(area_id: params[:area_id])
    end
    
    if params[:employment_type].present?
        jobs = jobs.where(employment_type:params[:employment_type])
    end

    if params[:salary_min].present?
        jobs = jobs.where("salary_min >= ?", params[:salary_min])
    end

    if params[:license_type_ids].present?
        jobs = jobs
        .join(:license_types)
        .where(license_types:{ id: params[:license_type_ids] })
        .distinct
    end



    scope :search, lambda { |params|
    jobs = all

    if params[:area_id].present?
      jobs = jobs.where(area_id: params[:area_id])
    end

    if params[:employment_type].present?
      jobs = jobs.where(employment_type: params[:employment_type])
    end

    if params[:salary_min].present?
      jobs = jobs.where("salary_min >= ?", params[:salary_min])
    end

    if params[:license_type_ids].present?
      jobs = jobs.joins(:license_types).where(license_types: { id: params[:license_type_ids] })
        .distinct
    end

    if params[:keyword].present?
        
    end


     if params[:keyword].present?
      keyword = "%#{sanitize_sql_like(params[:keyword])}%"
      jobs = jobs.where("title LIKE ? OR description LIKE ?", keyword, keyword)
    end

    jobs

    }
end



