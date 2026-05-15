class Application < ApplicationRecord
  belongs_to :user
  belongs_to :job
  validates :status, presence: true


  enum :status, { pending:0, reviewing:1, accepted:2, rejected:3,withdrawn:4 }

  scope :active, -> { where.not(status: :withdrawn) } 

  def withdrawable?
    pending? || reviewing?
  end

  
end


=begin
応募の関連、enum、validation、重複応募防止、取り下げ可否を定義する。

必要な関連:

- `belongs_to :user`
- `belongs_to :job`

必要なenum:

- `pending`
- `reviewing`
- `accepted`
- `rejected`
- `withdrawn`

必要なvalidation:

- `status`
- 同じユーザーが同じ求人に有効な応募を重複作成できないこと

必要なscope:

- `active`

必要なメソッド:

- `withdrawable?`

### 指示

- `active` は `withdrawn` 以外を返す
- 重複応募チェックでは、取り下げ済み応募は重複扱いにしない
- `withdrawable?` は `pending` と `reviewing` のときだけtrueにする
=end