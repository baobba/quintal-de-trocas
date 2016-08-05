class AddFieldsToUsers < ActiveRecord::Migration
  def change
    add_column :users, :complement, :string
    add_column :users, :neighborhood, :string
    add_column :users, :newsletter, :boolean
  end
end
