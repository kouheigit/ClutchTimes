FactoryBot.define do
  factory :license_type do
    sequence(:name) { |n| "免許#{n}" }
    sequence(:code) { |n| "license_#{n}" }
  end
end
