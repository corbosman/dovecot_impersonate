#!/usr/bin/perl

use strict;
use warnings;
use Getopt::Long;
use Config::Tiny;
use MIME::Lite;

# Get from command lineenvironmen
my $remoteAddress;
my $serverAddress;
my $account;
my $master;

GetOptions(
    'remote-ip=s' => \$remoteAddress,
    'server-ip=s' => \$serverAddress,
    'master=s' => \$master,
    'account=s' => \$account);

# Get variables from config files
my $Config = Config::Tiny->new;
$Config = Config::Tiny->read('/etc/dovecot/login-report.ini');

my $emailDest  = $Config->{Email}->{Dest};
my $emailFrom  = $Config->{Email}->{From};
my $emailCc    = $Config->{Email}->{Cc} || '';
my $emailBcc   = $Config->{Email}->{Bcc} || '';
my $smtpServer = $Config->{Email}->{Server} || 'localhost';

# be sure that the emails are correctly handled
$account =~ s/ /./g;
$master =~ s/ /./g;

# Send a message to whom is concerned
my $emailSubject = 'Master login facility used';
my $emailBody = "The master login facility has been used:\n\n";
$emailBody .= "- Server address   : $serverAddress\n";
$emailBody .= "- Remote address   : $remoteAddress\n";
$emailBody .= "- Account accessed : $account\n";
$emailBody .= "- Master account   : $master\n";
$emailBody .= "\n\n";

# print $emailBody;
my $msg = MIME::Lite->new(
From     => $emailFrom,
To       => $emailDest,
Cc       => $emailCc,
Bcc      => $emailBcc,
Subject  => $emailSubject,
Data     => $emailBody);
$msg->send('smtp', $smtpServer);

# $msg->send('smtp', $smtpServer, AuthUser => $mailUser, AuthPass => $mailPassword);

