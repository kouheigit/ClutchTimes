class CreateLicenseTypes < ActiveRecord::Migration[7.1]
  def change
    create_table :license_types do |t|
      t.string :name
      t.string :code

      t.timestamps
    end
  end
end
