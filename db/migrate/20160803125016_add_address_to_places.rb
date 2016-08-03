class AddAddressToPlaces < ActiveRecord::Migration
  def change
    add_column :places, :is_active, :boolean
    add_column :places, :complement, :string
    add_column :places, :neighborhood, :string
  end
end
