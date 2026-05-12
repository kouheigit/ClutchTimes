class User < ApplicationRecord
  has_many :applications
  has_many :applied_jobs, through: :applications, source: :job

  def applied_to?(job)
    jobs.exists?(job.id)
  end

  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :validatable
end


=begin
- `applications` はユーザー削除時に一緒に削除する
- `applied_to?(job)` では取り下げ済み以外の応募があるか確認する
- 応募ボタン表示や重複応募防止で使えるようにする
=end