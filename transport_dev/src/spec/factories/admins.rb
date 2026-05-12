FactoryBot.define do
  factory :admin do
    sequence(:email) { |n| "admin#{n}@example.com" }
    name { "管理者サンプル" }
    password { "password" }
    password_confirmation { "password" }
  end
end
