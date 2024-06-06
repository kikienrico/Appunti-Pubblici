# Multiplazione TDM
- N maiuscola
	$N=N.Canali$

- n minuscola
	$n=bit$

- Canale B
	$B_{ch}=f_{max}-f_{min}$ 

- Deve essere $f_{TRAMA}\geq2\times{B_{CH}}$ , scelgo un valore io (hz)

- Trama
	$T_{trama}=\frac{1}{f_{TRAMA}}$ | Es. $f_{trama}$ = 12Khz -> $T_{trama}=\frac{1}{12\times{10^3}}$

- Frequenza Mux
	$f_{mux}=2^n\times{f_{trama}}$

- Tbit
	$T_{bit}=\frac{1}{f_{mux}}$

- Fch
	$f_{ch}=\frac{n}{T_{trama}} | n\times{f_{trama}}$

- Fct
	$f_{ct}=n-N\times{f_{trama}}$

# S/H
- Fc (hz)
	$f_{c}\geq_{2}\times{f_{s}}$

- Fc (hz)
	$f_{c}\geq_{5}\times{f_{s}}$

- Tempo di campionamento (s/us)
	$T_{c}=\frac{1}{f_{c}}$

- Tempo di campionamento
	$T_{c}=h\times{R}\times{C}$ (5 perché C si carica dopo 5s di tempo)

- R la scelgo io (es. 10k$\ohm$)

- C (F)
	$C=\frac{T_{c}}{5\times{R}}$

# A/D

- $V_{fs}=$ Valore di fondo scala

- Quantizzazione (es. n=3) -> Tabella di verità
	$2^n=2^3=8$

- Tabella di verità
	Alternarsi di 0 e 1, Vout è Q

| $2^{2^2}$ | $1^{2^1}$ | $0^{2^0}$ | $V_{out}$ |
| --------- | --------- | --------- | --------- |
| 0         | 0         | 0         | 0         |

- Formula fondamentale di quantizzazione
	$2^nV_{i}=N\times{V_{fs}}$ -> $N_{1}=\frac{2^n\times{V_{i1}}}{V_{fs}}$ -> Assegno i valori al grafico (110, 001 etc...)

- Risoluzione
	$Q=\frac{V_{fs}}{2^n}$ ($V_{out}$)

- Massimo errore di quantizzazione
	$\xi_{Q}=\frac{Q}{2}$

- Fs max
$fs_{max}=\frac{1}{2^n\times{\pi}}\times{T_{Q}}$
