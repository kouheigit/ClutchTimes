class CreateJobs < ActiveRecord::Migration[7.1]
  def change
    create_table :jobs do |t|
      t.references :company, null: false, foreign_key: true
      t.string :title
      t.text :description
      t.integer :employment_type
      t.integer :salary_type
      t.integer :salary_min
      t.integer :salary_max
      t.references :area, null: false, foreign_key: true
      t.integer :status
      t.datetime :published_at
      t.datetime :expires_at

      t.timestamps
    end
  end
end
