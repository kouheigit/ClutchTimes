FactoryBot.define do
  factory :area do
    sequence(:name) { |n| "エリア#{n}" }
    sequence(:pref_code) { |n| n }
  end
end
