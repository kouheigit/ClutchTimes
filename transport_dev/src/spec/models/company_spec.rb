require 'rails_helper'

RSpec.describe Company, type: :model do
  describe "devise" do
    it "uses an independent company authentication scope" do
      expect(described_class.devise_modules).to include(
        :database_authenticatable,
        :registerable,
        :recoverable,
        :rememberable,
        :validatable
      )
      expect(Rails.application.routes.url_helpers.new_company_session_path).to eq("/companies/sign_in")
    end
  end

  describe "associations" do
    it { is_expected.to have_many(:jobs).dependent(:destroy) }
  end

  describe "validations" do
    it { is_expected.to validate_presence_of(:name) }
  end
end
