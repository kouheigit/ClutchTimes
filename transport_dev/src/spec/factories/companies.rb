FactoryBot.define do
  factory :company do
    sequence(:email) { |n| "company#{n}@example.com" }
    name { "サンプル運送株式会社" }
    password { "password" }
    password_confirmation { "password" }
  end
end
