require 'rails_helper'

RSpec.describe Application, type: :model do
  describe "associations" do
    it { is_expected.to belong_to(:user) }
    it { is_expected.to belong_to(:job) }
  end

  describe "validations" do
    it { is_expected.to validate_presence_of(:status) }

    it "prevents duplicate active applications for the same user and job" do
      application = FactoryBot.create(:application, status: :pending)
      duplicate = FactoryBot.build(:application, user: application.user, job: application.job, status: :pending)

      expect(duplicate).not_to be_valid
      expect(duplicate.errors[:base]).to include("すでにこの求人に応募しています")
    end

    it "allows reapplying after withdrawal" do
      application = FactoryBot.create(:application, status: :withdrawn)
      duplicate = FactoryBot.build(:application, user: application.user, job: application.job, status: :pending)

      expect(duplicate).to be_valid
    end
  end

  describe "enums" do
    it do
      expect(described_class.statuses).to eq(
        "pending" => 0,
        "reviewing" => 1,
        "accepted" => 2,
        "rejected" => 3,
        "withdrawn" => 4
      )
    end
  end

  describe "#withdrawable?" do
    it "is true for pending and reviewing applications" do
      expect(FactoryBot.build(:application, status: :pending)).to be_withdrawable
      expect(FactoryBot.build(:application, status: :reviewing)).to be_withdrawable
    end

    it "is false for final statuses" do
      expect(FactoryBot.build(:application, status: :accepted)).not_to be_withdrawable
      expect(FactoryBot.build(:application, status: :rejected)).not_to be_withdrawable
      expect(FactoryBot.build(:application, status: :withdrawn)).not_to be_withdrawable
    end
  end
end
