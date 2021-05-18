#!/usr/bin/env bash
#
# Anax script utility
#

#
# Globals (prefer none)
#
readonly DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"



#
# Print out current version
#
version()
{
    printf "v1.0.0 (2020-05-06)\\n"
}



#
# Print out how to use
#
usage()
{
    printf "\
Utility to work with Anax web sites.
Usage: anax.bash [options] <command> [command...]
Command:
 scaffold                    Run the scaffolding scripts.
 version                     Show info on how to use it.
Options:
 --debug, -d                 Be verbose to ease debug.
 --force, -f                 Force operation even though it should not.
 --help, -h                  Show info on how to use it.
 --no-composer-install, -nc  No 'composer install'
 --version, -v               Show the version number and date.
"
}



#
# Print out how to use
#
bad_usage()
{
    [[ -n $1 ]] && printf "%s\\n" "$1"

    printf "\
For an overview of the command, execute:
anax --help
"
}



#
# Error while processing
#
fail()
{
    local red
    local normal

    red=$(tput setaf 1)
    normal=$(tput sgr0)

    printf "%s %s\\n" "${red}[FAILED]${normal}" "$*"
    exit 2
}



#
# Print a header
#
header()
{
    printf "\033[32;01m>>> -------------- %-20s -------------------------\033[0m\n" "$1"
}



#
# Compatible sed -i.
# https://stackoverflow.com/a/4247319/341137
# arg1: Expression.
# arg2: Filename.
#
sedi()
{
    sed -i.bak "$1" "$2"
    rm -f "$2.bak"
}



#
# Exit with an error message
# $1 the message to display
# $2 an optional exit code, default is 1
#
error()
{
    echo "$1" >&2
    exit "${2:-1}"
}



#
# Do scaffolding from a directory
# $1 name to the directory
#
scaffold()
{
    printf "Execute scripts from '$1/'\n"
    for file in $1/*.bash; do
        [[ $DEBUG ]] && printf " $file\n"
        printf "."
        source "$file"
    done
    printf " done scaffolding\n"
}



#
# Do scaffolding
#
anax-scaffold()
{
    local path="$DIR/scaffold.d"

    header "Anax scaffold"
    [[ $NO_COMPOSER_INSTALL ]] || composer install --no-interaction
    scaffold "$path"
}



#
# Setup a theme
#
anax-theme()
{
    local path="$DIR/theme.d"

    header "Anax scaffold theme"
    scaffold "$path"
}



#
# Setup cimage
#
anax-cimage()
{
    local path=".anax/cimage.d"

    header "Anax scaffold cimage"
    scaffold "$path"
}



#
# Always have a main
#
main()
{
    local COMMAND=()

    while (( $# )); do
        case "$1" in
            --debug | -d)
                DEBUG=1
                shift
            ;;

            --force | -f)
                FORCE=1
                shift
            ;;

            --help | -h | help)
                usage
                exit 0
            ;;

            --no-composer-install | -nc)
                NO_COMPOSER_INSTALL=1
                shift
            ;;

            --version | -v | version)
                version
                exit 0
            ;;

            cimage   | \
            scaffold | \
            theme    )
                COMMAND+=($1)
                shift
            ;;

            *)
                bad_usage "Unknown option/command/argument '$1'."
                exit 1
            ;;
        esac
    done



    # Execute the command(s)
    for i in "${COMMAND[@]}"
    do
       if type -t anax-"$i" | grep -q function; then
           anax-"$i"
       else
           bad_usage "Missing command."
           exit 1
       fi
    done
}

main "$@"
