class CreateExchange < ActiveRecord::Migration
  def change
    create_table :exchanges do |t|
      t.string :status
      t.integer :toy_from
      t.integer :toy_to, foreign_key: true
      t.time :exchange_time
      t.date :exchange_date
      t.string :exchange_type
      t.text :message
      t.references :user, index: true

      t.timestamps null: false
    end
  end
end
