FactoryBot.define do
  factory :job do
    association :company
    association :area
    title { "都内ルート配送ドライバー" }
    description { "固定ルートで配送する仕事です。" }
    employment_type { :full_time }
    salary_type { :monthly }
    salary_min { 280_000 }
    salary_max { 360_000 }
    status { :published }
    published_at { Time.current }
    expires_at { 30.days.from_now }
  end
end
