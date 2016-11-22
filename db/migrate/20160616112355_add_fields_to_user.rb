class AddFieldsToUser < ActiveRecord::Migration
  def change
    add_column :users, :username, :string
    add_column :users, :name, :string
    add_column :users, :birthday, :date
    add_column :users, :phone, :string
    add_column :users, :gender, :string

    add_column :users, :street, :string
    add_column :users, :city, :string
    add_column :users, :state, :string
    add_column :users, :zipcode, :string
    add_column :users, :latitude, :float
    add_column :users, :longitude, :float
  end
end
