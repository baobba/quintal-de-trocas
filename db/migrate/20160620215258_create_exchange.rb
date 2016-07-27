class CreateExchange < ActiveRecord::Migration
  def change
    create_table :exchanges do |t|
      # t.string :status
      t.integer :toy_from
      t.integer :toy_to, foreign_key: true
      # t.time :exchange_time
      # t.date :exchange_date
      t.string :exchange_type
      t.string :exchange_deliver
      # t.text :message

      t.integer :rating_from
      t.integer :rating_to
      t.boolean :finalized, default: nil
      t.datetime :finalized_at
      t.boolean :accepted

      t.references :user, index: true

      t.timestamps null: false
    end
  end
end
