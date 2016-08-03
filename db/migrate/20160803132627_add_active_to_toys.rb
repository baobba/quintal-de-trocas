class AddActiveToToys < ActiveRecord::Migration
  def change
    add_column :toys, :is_active, :boolean
    add_column :toys, :deleted_at, :datetime
    add_column :toys, :neighborhood, :string

    add_column :users, :deleted_at, :datetime
  end
end
