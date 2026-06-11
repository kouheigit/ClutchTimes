class Job < ApplicationRecord
    scope :published, -> { where(published: true) }
    scope :active, -> { published.where("expires_at IS NULL OR expires_at > ?", Time.current)}
    scope :expirex,-> { published.where("expires_at >= ?", Time.current)}


    area_id   employment_type

    scope :search, ->(params) {
       jobs = all

       if params[:area_id].present? 
        jobs = jobs.where(area_id: params[:area_id])
       end

       if params[:employment_type].present?
        jobs = jobs.where(employment_type: params[:employment_type])
       end

       if params[:salary_min].present?
        jobs = jobs.where("salary_min >=?",params[:salary_min])
       end

       
    if params[:license_type_ids].present?
      jobs = jobs
        .joins(:license_types)
        .where(license_types: { id: params[:license_type_ids] })
        .distinct
    end

    if params[:keyword].present?
      keyword = "%#{params[:keyword]}%"
      jobs = jobs.where("title LIKE ? OR description LIKE ?",keyword,keyword)
    end
       
    }

    validates :title, presence: true
    validates :description, presence: true
    validates :employment_type, presence: true
    validates :salary_type, presence: true
    validates :status, presence:true


    belongs_to :company :area
    has_many :applications, dependent :destroy
    has_and_belongs_to_many :license_types
    
    enum :employment_type, { full_time:0,part_time:1 contract:2 temporary:3 }
    enum :salary_type, { monthly:0,hourly:1,annual:2}
    enum :status, { draft:0,published:1,closed:2 }

    
       



=begin

必要なscope:

- `published`
- `active`
- `expired`
- `search`

### 指示

- `active` は公開中かつ期限切れでない求人を返す
- `expired` は公開中かつ期限切れの求人を返す
- `search` では以下の条件で絞り込めるようにする
  - `area_id`
  - `employment_type`
  - `salary_min`
  - `license_type_ids`
  - `keyword`
- `license_type_ids` 検索ではjoinして重複求人を返さないようにする
=end
    
    
end
