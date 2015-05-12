
require 'rubygems'
require 'mechanize'
require 'nokogiri'
require 'pp'

login_url = "http://unitrix.cs.unipi.gr/grader.php?oker=1"

a = Mechanize.new
a.verify_mode = OpenSSL::SSL::VERIFY_NONE

while true do
    a.get( login_url )
	sleep( 5 )
end
