class AddToyIdToCredits < ActiveRecord::Migration
  def change
    add_reference :credits, :toy, index: true, foreign_key: true
  end
end
