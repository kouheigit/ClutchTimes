require 'rails_helper'

RSpec.describe User, type: :model do
  describe "devise" do
    it "uses an independent user authentication scope" do
      expect(described_class.devise_modules).to include(
        :database_authenticatable,
        :registerable,
        :recoverable,
        :rememberable,
        :validatable
      )
      expect(Rails.application.routes.url_helpers.new_user_session_path).to eq("/users/sign_in")
    end
  end

  describe "associations" do
    it { is_expected.to have_many(:applications).dependent(:destroy) }
    it { is_expected.to have_many(:applied_jobs).through(:applications).source(:job) }
  end

  describe "#applied_to?" do
    it "returns true when the user has an active application for the job" do
      application = create(:application, status: :pending)

      expect(application.user).to be_applied_to(application.job)
    end

    it "returns false when the only application is withdrawn" do
      application = create(:application, status: :withdrawn)

      expect(application.user).not_to be_applied_to(application.job)
    end
  end
end
