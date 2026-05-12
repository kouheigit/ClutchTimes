FactoryBot.define do
  factory :user do
    sequence(:email) { |n| "user#{n}@example.com" }
    name { "求職者サンプル" }
    password { "password" }
    password_confirmation { "password" }
  end
end
