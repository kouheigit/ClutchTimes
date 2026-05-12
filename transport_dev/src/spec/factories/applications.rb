FactoryBot.define do
  factory :application do
    association :user
    association :job
    status { :pending }
    message { "応募します。" }
  end
end
