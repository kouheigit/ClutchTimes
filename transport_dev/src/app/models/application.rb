class Application < ApplicationRecord
  belongs_to :user
  belongs_to :job
  validates :status, presence: true
  validate :prevent_duplicate_active_application

  enum :status, { pending:0, reviewing:1, accepted:2, rejected:3,withdrawn:4 }
  scope :active, -> { where.not(status: :withdrawn) } 

  def withdrawable?
    pending? || reviewing?
  end


  #これはバリデーとをかけたってことになる
  #validates :status, presence: true
   # status = ['require'] statusは必須ってことになる

  private

  def prevent_duplicate_active_application
    return if withdrawn?
    
    if Application.active.where(user_id: user_id, job_id: job_id).where.not(id: id).exists?
      errors.add(:base,"すでにこの求人に応募しています")
    end
  end  
end



