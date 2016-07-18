class Article < ActiveRecord::Base
  belongs_to :user
  validates :title, :body, presence: true

  def to_param
    [id, title.parameterize].join("-")
  end
end
