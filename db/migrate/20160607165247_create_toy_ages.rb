class CreateToyAges < ActiveRecord::Migration
  def change
    create_table :toy_ages do |t|
      t.string :title

      t.timestamps null: false
    end
  end
end
