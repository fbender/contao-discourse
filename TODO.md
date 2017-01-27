# TODOs

## documentation

Doc for admins to explain usage, options and what they do.

## auto-logout

Automatically logout the user from Discourse if user is revoked forum privileges
in Contao, so s/he can no longer use Discourse even if session is still valid.
May apply to other scenarios as well, see Contao hooks!

*Applies to Contao module.*

## moderator group support

Allow a Contao group to act as Discourse moderators. Pass on moderator flag in
SSO payload. Requires new Contao settings for selecting user/member groups to be
granted moderator flag.

May be done similarly for Admin privilege. Consider risk analysis.

*Applies to Contao module. May impact library.*

## forum group support

Enable passing Contao user/member group membership to Discourse, i.e. update
Discourse group membership based on Contao group membership. Probably requires a
new layer/class to talk to the API (instead of using SSO payload).

*Applies to library, Contao module.*

## error on invalid request

Instead of returning nothing (blank) when either `sso` or `sig` parameter is
missing, throw an error. The SSO module should be on a login-protected page
anyway. Maybe throw on missing either one when other one is set?

*Applies to Contao module.*

## error handling

Catch library exceptions and provide custom error messages. More logging, e.g.
on invalid request.

*Applies to Contao module.*

## parse endpoint in settings

Instead of parsing the setting for the SSO endpoint on-the-fly, do so in the
onsave callback in the settings. May still require a sanity check in the module,
though.

*Applies to Contao module.*

## per-module settings

Move (at least some) settings from the general Contao settings to the module
settings.

*Applies to Contao module.*
