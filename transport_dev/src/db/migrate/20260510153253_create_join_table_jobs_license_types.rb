class CreateJoinTableJobsLicenseTypes < ActiveRecord::Migration[7.1]
  def change
    create_join_table :jobs, :license_types do |t|
      # t.index [:job_id, :license_type_id]
      # t.index [:license_type_id, :job_id]
    end
  end
end
