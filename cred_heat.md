Based on the codebase, you have:
Likes on snaccs and comments
Comments and replies
Quote reposts (reposting with commentary)
Views (tracked on snaccs)
Vibetags (hashtag-like system with ~tag)
Visibility scopes (campus vs global)
Proposed System Design
Heat Score (Viral/Trending - Short Term)
Concept: Measures recent viral activity and engagement velocity. Decays rapidly (24-72 hours). Components:
Weighted Actions (within 24-72 hours):
View: +0.5 points
Like: +2 points
Comment: +5 points
Quote repost: +8 points
Reply to comment: +3 points
Velocity Multipliers:
If 5+ actions in first hour: 1.5x multiplier
If 10+ actions in first 3 hours: 2x multiplier
If trending on campus (top 10 most engaged): 1.3x multiplier
If goes global (campus → global visibility): 1.5x multiplier
Time Decay:
0-6 hours: 100% value
6-12 hours: 80% value
12-24 hours: 50% value
24-48 hours: 25% value
48-72 hours: 10% value
72+ hours: Archived (heat = 0)
Heat Badges/Icons:
🔥 Fire (100-499 heat)
🔥🔥 Double Fire (500-999 heat)
💥 Explosion (1000+ heat) - "This snacc is going viral!"
Use Cases:
Show "trending" feed sorted by heat
Display fire emoji next to hot posts
Notify users when their post is heating up
Campus leaderboard for hottest snaccs this week
Cred Score (Reputation - Long Term)
Concept: Cumulative reputation earned through consistent quality engagement and community trust. Never decays. Components:
Earning Cred:
Post a snacc: +1 cred
Receive a like on snacc: +2 cred
Receive a comment: +3 cred
Get quoted: +5 cred
Daily login streak: +5 cred per day (max 7 days, then resets)
Account age milestones:
1 month: +50 cred
3 months: +150 cred
6 months: +300 cred
1 year: +1000 cred
Have a post reach 1000+ heat: +100 cred (one-time bonus per post)
Comment with 10+ likes: +10 cred
Losing Cred (moderation/quality control):
Post deleted by you: -5 cred
Post reported and confirmed: -50 cred
Account warning: -100 cred
Spam flagged: -25 cred
Cred Tiers & Badges:
0-99: Newbie (no badge)
100-499: Active (🟢 green badge)
500-999: Regular (🔵 blue badge)
1,000-4,999: Established (🟣 purple badge)
5,000-9,999: Influential (🟠 orange badge)
10,000+: Legend (⭐ gold star badge)
Cred Benefits/Perks:
500+ cred: Early access to new features
1,000+ cred: Verified checkmark next to username
5,000+ cred: Ability to create custom vibetags that appear in autocomplete
10,000+ cred: Profile highlight in "Top Contributors" section
Database Schema
