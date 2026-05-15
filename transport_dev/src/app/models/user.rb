class User < ApplicationRecord
  has_many :applications,dependent: :destroy
  has_many :applied_jobs, through: :applications, source: :job

  def applied_to?(job)
    applications.active.exists?(job_id: job.id)
  end

  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :validatable
end

