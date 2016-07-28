CarrierWave.configure do |config|
  config.fog_provider = 'fog/aws'                        # required
  config.fog_credentials = {
    :provider               => 'AWS',
    :aws_access_key_id      => ENV['AWS_ACCESS_KEY']        || '',
    :aws_secret_access_key  => ENV['AWS_SECRET_ACCESS_KEY'] || '',
    :region                 => ENV['AWS_REGION']            || '',
    :path_style             => true
  }
  config.fog_directory  = ENV['AWS_S3_BUCKET']              || ''      # required
  config.fog_public     = false                                        # optional, defaults to true
  config.fog_attributes = { 'Cache-Control' => "max-age=#{365.day.to_i}" } # optional, defaults to {}
end