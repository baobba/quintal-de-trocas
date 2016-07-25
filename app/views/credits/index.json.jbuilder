json.array!(@credits) do |credit|
  json.extract! credit, :id
  json.url credit_url(credit, format: :json)
end
