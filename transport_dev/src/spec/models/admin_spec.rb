require 'rails_helper'

RSpec.describe Admin, type: :model do
  describe "devise" do
    it "uses an independent admin authentication scope" do
      expect(described_class.devise_modules).to include(
        :database_authenticatable,
        :registerable,
        :recoverable,
        :rememberable,
        :validatable
      )
      expect(Rails.application.routes.url_helpers.new_admin_session_path).to eq("/admins/sign_in")
    end
  end
end
