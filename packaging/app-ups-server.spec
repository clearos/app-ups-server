
Name: app-ups-server
Epoch: 1
Version: 1.6.5
Release: 1%{dist}
Summary: UPS Server
License: GPLv3
Group: ClearOS/Apps
Packager: UWS
Vendor: UWS
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base

%description
The UPS Server app is powered by the Network UPS Tools (NUT) project providing support for Power Devices.

%package core
Summary: UPS Server - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core

%description core
The UPS Server app is powered by the Network UPS Tools (NUT) project providing support for Power Devices.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/ups_server
cp -r * %{buildroot}/usr/clearos/apps/ups_server/


%post
logger -p local6.notice -t installer 'app-ups-server - installing'

%post core
logger -p local6.notice -t installer 'app-ups-server-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/ups_server/deploy/install ] && /usr/clearos/apps/ups_server/deploy/install
fi

[ -x /usr/clearos/apps/ups_server/deploy/upgrade ] && /usr/clearos/apps/ups_server/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-ups-server - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-ups-server-core - uninstalling'
    [ -x /usr/clearos/apps/ups_server/deploy/uninstall ] && /usr/clearos/apps/ups_server/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/ups_server/controllers
/usr/clearos/apps/ups_server/htdocs
/usr/clearos/apps/ups_server/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/ups_server/packaging
%dir /usr/clearos/apps/ups_server
/usr/clearos/apps/ups_server/deploy
/usr/clearos/apps/ups_server/language
/usr/clearos/apps/ups_server/libraries
