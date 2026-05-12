class AddIndexesToJobsLicenseTypes < ActiveRecord::Migration[7.1]
  def change
    add_index :jobs_license_types, [:job_id, :license_type_id], unique: true
    add_index :jobs_license_types, [:license_type_id, :job_id]
  end
end
