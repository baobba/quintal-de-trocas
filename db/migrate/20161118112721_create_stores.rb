class CreateStores < ActiveRecord::Migration
  def change
    create_table :stores do |t|
      t.string :name
<<<<<<< HEAD
      t.string :pagseguro_notification_code
=======
>>>>>>> cf00e2e780b10aa9b0512eb642e00095b51aac69
      t.references :user, index: true, foreign_key: true

      t.timestamps null: false
    end
  end
end
