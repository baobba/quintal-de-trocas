class CreateToyImages < ActiveRecord::Migration
  def change
    create_table :toy_images do |t|
      t.references :toy, index: true, foreign_key: true
      t.string :image
      t.boolean :featured, default: false

      t.timestamps null: false
    end
  end
end
