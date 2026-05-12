class User < ApplicationRecord
  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :validatable

  has_many :applications, dependent: :destroy
  has_many :applied_jobs, through: :applications, source: :job

  def applied_to?(job)
    applications.active.exists?(job: job)
  end
end
