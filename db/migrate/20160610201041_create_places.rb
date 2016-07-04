class CreatePlaces < ActiveRecord::Migration
  def change
    create_table :places do |t|
      t.string :title
      t.text :description
      t.string :office_hours
      t.string :phone
      t.string :phone_alt
      t.references :user, index: true

      t.string :zipcode
      t.string :street
      t.string :city
      t.string :state
      t.float :latitude
      t.float :longitude

      t.timestamps null: false
    end
  end
end
