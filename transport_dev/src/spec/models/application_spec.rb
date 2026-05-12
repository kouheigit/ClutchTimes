require 'rails_helper'

RSpec.describe Application, type: :model do
  describe "associations" do
    it { is_expected.to belong_to(:user) }
    it { is_expected.to belong_to(:job) }
  end

  describe "validations" do
    it { is_expected.to validate_presence_of(:status) }

    it "prevents duplicate active applications for the same user and job" do
      application = create(:application, status: :pending)
      duplicate = build(:application, user: application.user, job: application.job, status: :pending)

      expect(duplicate).not_to be_valid
      expect(duplicate.errors[:base]).to include("すでにこの求人に応募しています")
    end

    it "allows reapplying after withdrawal" do
      application = create(:application, status: :withdrawn)
      duplicate = build(:application, user: application.user, job: application.job, status: :pending)

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

  describe ".active" do
    it "returns applications that are not withdrawn" do
      active_application = create(:application, status: :pending)
      withdrawn_application = create(:application, status: :withdrawn)

      expect(described_class.active).to include(active_application)
      expect(described_class.active).not_to include(withdrawn_application)
    end
  end

  describe "#withdrawable?" do
    it "is true for pending and reviewing applications" do
      expect(build(:application, status: :pending)).to be_withdrawable
      expect(build(:application, status: :reviewing)).to be_withdrawable
    end

    it "is false for final statuses" do
      expect(build(:application, status: :accepted)).not_to be_withdrawable
      expect(build(:application, status: :rejected)).not_to be_withdrawable
      expect(build(:application, status: :withdrawn)).not_to be_withdrawable
    end
  end
end
