class CreateCredits < ActiveRecord::Migration
  def change
    create_table :credits do |t|
      t.references :user, index: true, foreign_key: true
      t.boolean :is_available
      t.references :exchange, index: true, foreign_key: true

      t.timestamps null: false
    end
  end
end
