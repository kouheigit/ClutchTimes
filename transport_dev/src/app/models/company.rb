class Company < ApplicationRecord
  has_many :jobs, dependent: :destroy
  validates :name, presence: true

  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :validatable
end
