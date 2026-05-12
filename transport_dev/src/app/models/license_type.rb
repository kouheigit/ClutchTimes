class LicenseType < ApplicationRecord
  has_and_belongs_to_many :jobs
  validates :name, presence: true
end
