#! /usr/bin/perl -w

#use strict;

my $dir = "/var/www/asterisk/graphs";
unlink glob "$dir/*admin.txt";
unlink glob "$dir/*admin.jpeg";
chdir $dir;


##### MAIN #####
&daily;
&weekly;
&ytd;
unlink glob "$dir/*admin.txt";
################

###########################################################################################
###########################################################################################

sub daily {
    chomp(my $month = `date +"%m"`);
    chomp(my $year  = `date +"%Y"`);
    my @cal_days   = qw(31 28 31 30 31 30 31 31 30 31 30 31);
    my @day = qw(01 02 03 04 05 06 07 08 09 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31);
    $month = "04";
    my @lines;
    my $line;
    for (my $count = 0; $count < $cal_days[$month - 1]; $count++){
	$line = `sudo mysql -N --user=asterisk --password=yourpassword -e \"use asterisk; select calldate, sum(billsec) from cdr where calldate like '$year-$month-$day[$count] %';\"`;
	# print $line . "\n";
	if ($line =~ m/NULL/){
	    $line = "$year-$month-$day[$count] 0";
	} else {
	    my ($d,$t,$v) = split (/\s+/,$line);
	    $line = "$year-$month-$day[$count] $v";
	}
	push (@lines, $line); 
    }    
    my $fname = "Daily_admin.txt";
    open (WKLY,">$dir/$fname");
    foreach my $l (@lines){
	print WKLY $l . "\n";
    }
    close (WKLY);
    &plot("Daily","$fname");
}

##############################################

sub weekly {
    chomp(my $month = `date +"%m"`);
    chomp(my $year  = `date +"%Y"`);
    my @months = qw(01 02 03 04 05 06 07 08 09 10 11 12);
    my @days   = qw(01 02 03 04 05 06 07 08 09 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31);
    $month = "04";
    my @lines;
    my @weeks;
    push (@weeks, "$year-$month-01","$year-$month-07","$year-$month-08", "$year-$month-14", "$year-$month-15", "$year-$month-21" ,"$year-$month-22", "$year-$month-31");
    my $line;
    for (my $count = 1; $count <= 7; $count +=2){
	$line = `sudo mysql -N --user=asterisk --password=yourpassword -e \"use asterisk; select calldate, sum(billsec) from cdr where calldate between '$weeks[$count - 1] %' and '$weeks[$count] %';\"`;
	# print $line . "\n";
	if ($line =~ m/NULL/){
	    $line = "$weeks[$count - 1] 0";
	} else {
	    my ($d,$t,$v) = split (/\s+/,$line);
	    $line = "$weeks[$count - 1] $v";
	}
	push (@lines, $line); 
    }
    my $fname = "Weekly_admin.txt";
    open (MON,">$dir/$fname");
    foreach my $l (@lines){
	print MON $l . "\n";
    }
    close (MON);
    &plot("Weekly","$fname");
}

##############################################

sub ytd {
    my @months = qw(01 02 03 04 05 06 07 08 09 10 11 12);
    chomp(my $month = `date +"%m"`);
    chomp(my $year  = `date +"%Y"`);
    my $line;
    my @lines;
    for (my $count = 0; $count <= $month; $count++){
	$line = `sudo mysql -N --user=asterisk --password=yourpassword -e "use asterisk; select calldate , sum(billsec) from cdr where calldate like '$year-$months[$count]-%'";`;
	# print $line . "\n";
	if ($line =~ m/NULL/){
	    $line = "$year-$months[$count] 0";
	} else {
	    my ($d,$t,$v) = split (/\s+/,$line);
	    $line = "$year-$months[$count] $v";
	}
	push (@lines, $line); 
    }
    my $fname = "YTD_admin.txt";
    open (YTD,">$dir/$fname");
    foreach my $l (@lines){
	print YTD $l . "\n";
    }
    close (YTD);
    &plot("YTD","$fname");
}

###############################################

sub plot {
    my $type = shift;
    my $file = shift;
    my $fname = $type . "_admin.jpeg";
    open (GNU,">gnu_$type");
    print GNU "set output \"$fname\" \n set title \"$type Usage for All Customers\" \n set style fill solid .8 \n set style data histogram \n set style fill solid border -1 \n set boxwidth 1.6 \n";
    print GNU "set autoscale \n unset log \n unset label \n set xtics rotate auto \n set ytics auto \n set grid ytics \n set xlabel \"Time\" \n  set ylabel \"Usage (in seconds)\" \n set terminal png small size 800,600 \n";
    print GNU "plot '$file' using 2:xtic(1) t 'Usage'";
    close (GNU);
    system "gnuplot gnu_$type";
    unlink "gnu_$type";
}

###########################################################################################
###########################################################################################
