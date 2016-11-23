# encoding: utf-8
desc "PG Backup"
namespace :backup do
  task :database => [:environment] do
    # stamp the filename
    datestamp = Time.now.strftime("%Y-%m-%d_%H-%M-%S")

    # drop it in the db directory temporarily
    backup_file = "#{Rails.root}/db/quintal_#{datestamp}_dump.sql.gz" 

    # dump the backup and zip it up
    sh "pg_dump -h localhost -U quintal quintal_production | gzip -c > #{backup_file}"     

    send_to_amazon(backup_file)

    # remove the file on completion so we don't clog up our app
    File.delete backup_file

    puts "Backup completed"
  end
end

def send_to_amazon(file_path)
  bucket = "quintal-backup"
  file_name = File.basename(file_path)
  AWS::S3::Base.establish_connection!(
    :access_key_id => ENV["AWS_ACCESS_KEY"],
    :secret_access_key => ENV["AWS_SECRET_KEY"])
  
  # push the file up
  AWS::S3::S3Object.store(file_name,File.open("#{file_path}"),bucket)
end