require 'rails_helper'

RSpec.describe Job, type: :model do
  describe "associations" do
    it { is_expected.to belong_to(:company) }
    it { is_expected.to belong_to(:area) }
    it { is_expected.to have_many(:applications).dependent(:destroy) }
    it { is_expected.to have_and_belong_to_many(:license_types) }
  end

  describe "validations" do
    it { is_expected.to validate_presence_of(:title) }
    it { is_expected.to validate_presence_of(:description) }
    it { is_expected.to validate_presence_of(:employment_type) }
    it { is_expected.to validate_presence_of(:salary_type) }
    it { is_expected.to validate_presence_of(:status) }
  end

  describe "enums" do
    it do
      expect(described_class.employment_types).to eq(
        "full_time" => 0,
        "part_time" => 1,
        "contract" => 2,
        "temporary" => 3
      )
    end

    it { expect(described_class.salary_types).to eq("monthly" => 0, "hourly" => 1, "annual" => 2) }
    it { expect(described_class.statuses).to eq("draft" => 0, "published" => 1, "closed" => 2) }
  end

  describe ".active" do
    it "returns published jobs that have not expired" do
      active_job = FactoryBot.create(:job, status: :published, expires_at: 1.day.from_now)
      draft_job = FactoryBot.create(:job, status: :draft, expires_at: 1.day.from_now)
      expired_job = FactoryBot.create(:job, status: :published, expires_at: 1.day.ago)

      expect(described_class.active).to include(active_job)
      expect(described_class.active).not_to include(draft_job, expired_job)
    end
  end

  describe ".search" do
    it "filters by area, employment type, salary, license, and keyword" do
      area = FactoryBot.create(:area)
      license_type = FactoryBot.create(:license_type)
      matching_job = FactoryBot.create(
        :job,
        area: area,
        employment_type: :full_time,
        salary_min: 300_000,
        title: "大型配送ドライバー"
      )
      matching_job.license_types << license_type
      FactoryBot.create(:job, area: area, employment_type: :part_time, salary_min: 300_000)

      result = described_class.search(
        area_id: area.id,
        employment_type: "full_time",
        salary_min: 250_000,
        license_type_ids: [license_type.id],
        keyword: "大型"
      )

      expect(result).to contain_exactly(matching_job)
    end
  end
end
