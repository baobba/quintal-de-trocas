class CreateToys < ActiveRecord::Migration
  def change
    create_table :toys do |t|
      t.string :title
      t.text :description
      t.references :toy_category, index: true, foreign_key: true
      t.references :toy_age, index: true, foreign_key: true
      t.references :user, index: true, foreign_key: true

      t.string :zipcode
      t.float :latitude
      t.float :longitude

      t.timestamps null: false
    end
  end
end
