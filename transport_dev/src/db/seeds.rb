user = User.find_or_initialize_by(email: "user@example.com")
user.update!(
  name: "求職者サンプル",
  password: "password",
  password_confirmation: "password"
)

company = Company.find_or_initialize_by(email: "company@example.com")
company.update!(
  name: "サンプル運送株式会社",
  password: "password",
  password_confirmation: "password"
)

admin = Admin.find_or_initialize_by(email: "admin@example.com")
admin.update!(
  name: "管理者サンプル",
  password: "password",
  password_confirmation: "password"
)

areas = [
  { name: "東京都", pref_code: 13 },
  { name: "神奈川県", pref_code: 14 },
  { name: "埼玉県", pref_code: 11 }
].index_by { |attributes| attributes[:name] }

areas.each_value do |attributes|
  Area.find_or_create_by!(name: attributes[:name]) do |area|
    area.pref_code = attributes[:pref_code]
  end
end

license_types = [
  { name: "普通自動車免許", code: "standard" },
  { name: "準中型自動車免許", code: "semi_medium" },
  { name: "中型自動車免許", code: "medium" },
  { name: "大型自動車免許", code: "large" },
  { name: "フォークリフト運転技能講習", code: "forklift" }
].index_by { |attributes| attributes[:code] }

license_types.each_value do |attributes|
  LicenseType.find_or_create_by!(code: attributes[:code]) do |license_type|
    license_type.name = attributes[:name]
  end
end

tokyo = Area.find_by!(name: "東京都")
kanagawa = Area.find_by!(name: "神奈川県")
standard = LicenseType.find_by!(code: "standard")
medium = LicenseType.find_by!(code: "medium")
large = LicenseType.find_by!(code: "large")
forklift = LicenseType.find_by!(code: "forklift")

jobs = [
  {
    title: "都内ルート配送ドライバー",
    description: "固定ルートで食品や日用品を配送する仕事です。",
    area: tokyo,
    employment_type: :full_time,
    salary_type: :monthly,
    salary_min: 280_000,
    salary_max: 360_000,
    status: :published,
    published_at: Time.current,
    expires_at: 30.days.from_now,
    license_types: [standard, medium]
  },
  {
    title: "夜間大型トラックドライバー",
    description: "関東圏の幹線輸送を担当します。",
    area: kanagawa,
    employment_type: :contract,
    salary_type: :monthly,
    salary_min: 350_000,
    salary_max: 450_000,
    status: :published,
    published_at: Time.current,
    expires_at: 45.days.from_now,
    license_types: [large, forklift]
  }
]

jobs.each do |attributes|
  licenses = attributes.delete(:license_types)
  job = Job.find_or_initialize_by(company: company, title: attributes[:title])
  job.update!(attributes.merge(company: company))
  job.license_types = licenses
end
