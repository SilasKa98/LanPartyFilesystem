<?php require_once __DIR__ . '/components/sidebar.php'; ?>
<!doctype html>
<html lang="de">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>LocalLoot - Tools</title>
<style>
body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:#f5f8ff;color:#102446}
.app-shell{display:grid;grid-template-columns:300px 1fr;min-height:100vh}.sidebar{background:#fff;border-right:1px solid #e2e9f7;padding:22px}.side-link{display:block;padding:12px 14px;border-radius:10px;color:#2e4468;text-decoration:none;font-weight:600;margin:4px 0}.side-link.active{background:#edf3ff;color:#1f6fff}.brand{display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit}.brand-logo{width:44px;height:44px;border-radius:10px;background:#e8f0ff;padding:5px;object-fit:contain}
.wrap{padding:24px;max-width:1200px}.panel{background:#fff;border:1px solid #dbe7ff;border-radius:16px;padding:18px;box-shadow:0 10px 30px rgba(20,60,130,.08)}
.row{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:12px}.input,.btn,textarea{border:1px solid #d4e3ff;border-radius:12px;padding:11px 12px;font:inherit}
textarea{width:100%;min-height:120px;resize:vertical}.btn{background:#1f6fff;color:#fff;border-color:#1f6fff;cursor:pointer;font-weight:700}.btn.ghost{background:#fff;color:#1f6fff}
#arena{margin-top:16px;min-height:240px;position:relative;overflow:hidden;border:1px dashed #c9dcff;border-radius:14px;background:linear-gradient(180deg,#f7fbff,#eff6ff)}
.ball{position:absolute;padding:7px 10px;border-radius:999px;background:#1f6fff;color:#fff;font-weight:700;font-size:.88rem;box-shadow:0 8px 20px rgba(31,111,255,.3);transition:transform .7s ease,left .7s ease,top .7s ease,background .7s ease}
.team-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;margin-top:14px}
.team{background:#fff;border:1px solid #dbe7ff;border-radius:14px;padding:12px}.team h3{margin:0 0 8px}.meta{color:#58709a;font-size:.9rem}.bracketWrap{background:#fff;border:1px solid #dbe7ff;border-radius:14px;padding:10px;overflow:auto}.bracketSvg{min-width:900px;height:420px}.node{fill:#1f6fff;stroke:#0f4ccc;stroke-width:1.5}.nodeText{fill:#fff;font-size:11px;font-weight:700}.edge{stroke:#7aa7ff;stroke-width:2;fill:none;stroke-dasharray:8 6;animation:dash 1.8s linear infinite}@keyframes dash{to{stroke-dashoffset:-28}}
</style>
</head>
<body>
<div class="app-shell">
<?php renderSidebar('tools'); ?>
<main class="wrap">
  <h1>🧰 Tools</h1>
  <div class="panel">
    <h2 style="margin-top:0">🎲 Team Randomizer (Skill-balanced)</h2>
    <p class="meta">Namen je Zeile eingeben mit optionalem Skill in Klammern, z. B. <code>Alex (7)</code>. Ohne Angabe wird Skill 5 genutzt.</p>
    <div class="row">
      <input id="teamSize" class="input" type="number" min="2" value="3" style="width:160px" placeholder="Teamgröße">
      <button class="btn" id="drawBtn">Teams auslosen</button>
      <button class="btn ghost" id="demoBtn">Demo-Daten</button><select id="tournamentType" class="input"><option value="single">Single Elimination</option><option value="double">Double Elimination (Light)</option><option value="roundrobin">Round Robin</option></select><button class="btn ghost" id="buildTournamentBtn">Turnierbaum erstellen</button>
    </div>
    <textarea id="players" placeholder="Namen, je Zeile&#10;Mia (8)&#10;Noah (6)&#10;...\n"></textarea>
    <div id="arena"></div>
    <div id="result" class="team-grid"></div><div id="tournament" class="team-grid" style="margin-top:16px"></div>
  </div>
</main>
</div>
<script>
const arena=document.getElementById('arena');
const result=document.getElementById('result');
const colors=['#1f6fff','#14b8a6','#7c3aed','#ef4444','#f59e0b','#0ea5e9','#db2777'];
function parsePlayers(text){
  return text.split(/\n+/).map(l=>l.trim()).filter(Boolean).map(line=>{const m=line.match(/^(.*?)\s*(?:\((\d+)\))?$/);const name=(m?.[1]||line).trim();const skill=Math.max(1,Math.min(10,parseInt(m?.[2]||'5',10)));return {name,skill};});
}
function shuffle(arr){
  const out=[...arr];
  for(let i=out.length-1;i>0;i--){const j=Math.floor(Math.random()*(i+1));[out[i],out[j]]=[out[j],out[i]];}
  return out;
}
function balanceTeams(players, teamSize){
  const teamCount=Math.ceil(players.length/teamSize);
  const teams=Array.from({length:teamCount},(_,i)=>({id:i,members:[],skill:0}));
  const randomized=shuffle(players);
  const sorted=[...randomized].sort((a,b)=>b.skill-a.skill);
  for(const p of sorted){
    teams.sort((a,b)=> (a.members.length-b.members.length) || (a.skill-b.skill) || (Math.random()-0.5));
    teams[0].members.push(p);teams[0].skill+=p.skill;
  }
  return shuffle(teams);
}
function renderBalls(players){
  arena.innerHTML='';
  const w=arena.clientWidth-90,h=arena.clientHeight-40;
  players.forEach((p,i)=>{const d=document.createElement('div');d.className='ball';d.textContent=`${p.name} (${p.skill})`;d.style.left=Math.max(0,Math.random()*w)+'px';d.style.top=Math.max(0,Math.random()*h)+'px';d.style.transform='scale(0.9)';arena.appendChild(d);setTimeout(()=>{d.style.transform='scale(1)'},30*i);});
}
function animateToTeams(teams){
  const balls=[...arena.querySelectorAll('.ball')];
  const colW=Math.max(180,arena.clientWidth/teams.length);
  let idx=0;
  teams.forEach((team,ti)=>{team.members.forEach((m,mi)=>{const b=balls[idx++]; if(!b) return; b.style.background=colors[ti%colors.length]; b.style.left=(ti*colW+12)+'px'; b.style.top=(18+mi*38)+'px';});});
}
function renderResult(teams){
  result.innerHTML='';
  teams.forEach((team,i)=>{const el=document.createElement('div');el.className='team';el.innerHTML=`<h3 style="color:${colors[i%colors.length]}">Team ${i+1}</h3><div class="meta">Gesamt-Skill: <strong>${team.skill}</strong></div><ul>${team.members.map(m=>`<li>${m.name} <small>(Skill ${m.skill})</small></li>`).join('')}</ul>`;result.appendChild(el);});
}

function pairSingle(teams){
  const shuffled=shuffle(teams);
  const rounds=[];
  let current=shuffled.map((t,i)=>({name:'Team '+(i+1),skill:t.skill,members:t.members}));
  while(current.length>1){
    const matches=[];
    for(let i=0;i<current.length;i+=2){
      const a=current[i], b=current[i+1]||{name:'BYE',skill:0,members:[]};
      matches.push({a,b});
    }
    rounds.push(matches);
    current=matches.map(m=>m.b.name==='BYE'?m.a:(m.a.skill>=m.b.skill?m.a:m.b));
  }
  return rounds;
}
function renderTournament(teams){
  const box=document.getElementById('tournament');
  const mode=document.getElementById('tournamentType').value;
  box.innerHTML='';
  if(!teams.length){return;}

  const drawBracket=(rounds,title)=>{
    const wrap=document.createElement('div');wrap.className='bracketWrap';
    const svg=document.createElementNS('http://www.w3.org/2000/svg','svg');svg.setAttribute('class','bracketSvg');
    const roundGap=220,nodeW=150,nodeH=28,startX=30,startY=30,slotGap=58;
    const positions=[];
    rounds.forEach((matches,ri)=>{positions[ri]=[];matches.forEach((m,mi)=>{const x=startX+ri*roundGap;const y=startY+mi*slotGap*Math.pow(2,ri);positions[ri][mi]={x,y,m};
      const rect=document.createElementNS(svg.namespaceURI,'rect');rect.setAttribute('x',x);rect.setAttribute('y',y);rect.setAttribute('width',nodeW);rect.setAttribute('height',nodeH);rect.setAttribute('rx',10);rect.setAttribute('class','node');svg.appendChild(rect);
      const text=document.createElementNS(svg.namespaceURI,'text');text.setAttribute('x',x+8);text.setAttribute('y',y+18);text.setAttribute('class','nodeText');text.textContent=`${m.a.name} vs ${m.b.name}`;svg.appendChild(text);
    });});
    for(let r=0;r<positions.length-1;r++){
      positions[r].forEach((p,i)=>{const next=positions[r+1][Math.floor(i/2)];if(!next)return;
        const path=document.createElementNS(svg.namespaceURI,'path');
        const x1=p.x+nodeW,y1=p.y+nodeH/2,x2=next.x,y2=next.y+nodeH/2,mx=(x1+x2)/2;
        path.setAttribute('d',`M ${x1} ${y1} C ${mx} ${y1}, ${mx} ${y2}, ${x2} ${y2}`);
        path.setAttribute('class','edge');svg.insertBefore(path,svg.firstChild);
      });
    }
    const h=document.createElement('h3');h.textContent=title;wrap.appendChild(h);wrap.appendChild(svg);box.appendChild(wrap);
  };

  if(mode==='single'){
    drawBracket(pairSingle(teams),'Single Elimination');
  } else if(mode==='double'){
    drawBracket(pairSingle(teams),'Upper Bracket');
    const lowerTeams=teams.map((t,i)=>({name:'Team '+(i+1),skill:Math.max(1,t.skill-1),members:t.members}));
    drawBracket(pairSingle(lowerTeams),'Lower Bracket');
  } else {
    const col=document.createElement('div');col.className='team';col.innerHTML='<h3>Round Robin Paarungen</h3>';
    for(let i=0;i<teams.length;i++){for(let j=i+1;j<teams.length;j++){const a='Team '+(i+1),b='Team '+(j+1);col.innerHTML+=`<div class="meta">${a} vs ${b}</div>`;}}
    box.appendChild(col);
  }
}

document.getElementById('drawBtn').onclick=()=>{
  const players=parsePlayers(document.getElementById('players').value);
  const size=Math.max(2,parseInt(document.getElementById('teamSize').value||'3',10));
  if(players.length<size){alert('Bitte mehr Spieler eintragen.');return;}
  const teams=balanceTeams(players,size);
  renderBalls(players);
  setTimeout(()=>animateToTeams(teams),500);
  setTimeout(()=>{renderResult(teams);window.lastTeams=teams;},1400);
};
document.getElementById('demoBtn').onclick=()=>{document.getElementById('players').value='Mia (8)\nNoah (6)\nLuca (4)\nEmma (9)\nFinn (5)\nLea (7)\nBen (3)\nNina (6)\nTom (8)';};
document.getElementById('buildTournamentBtn').onclick=()=>{if(!window.lastTeams||!window.lastTeams.length){alert('Bitte zuerst Teams auslosen.');return;}renderTournament(window.lastTeams);};
</script>
</body>
</html>
