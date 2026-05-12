require 'rails_helper'

RSpec.describe Area, type: :model do
  describe "associations" do
    it { is_expected.to have_many(:jobs) }
  end

  describe "validations" do
    it { is_expected.to validate_presence_of(:name) }
  end
end
