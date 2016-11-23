slack_url = "https://hooks.slack.com/services/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"
slack_usernmae = "osnysantos"
slack_channel = Rails.env.development? ? "#site-test" : "#site"

NOTIFIER = Slack::Notifier.new slack_url, 
  channel: slack_channel,
  username: slack_usernmae