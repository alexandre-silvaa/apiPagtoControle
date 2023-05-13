<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Notas por remetente</title>

	{{-- CSS bootstrap --}}
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	{{-- JS bootstrap --}}
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>
<body>
	
	<nav class="navbar navbar-light bg-light">
	  <div class="container">
	    <a class="navbar-brand mx-auto" href="https://www.azapfy.com.br/" target="_blank">
	      <img src="{{url('images/logo.png')}}" alt="">
	    </a>
	  </div>
	</nav>

	<main>
		<div class="row">
			<div class="col-12">
				<div class="table-responsive-xl p-5">
					<table class="table table-borderless table-striped table-hover table-sm">
						<thead class="thead-light">
							<tr>
								<th>CNPJ</th>
								<th>Nome Remetente</th>
								<th>Valor Total</th>
								<th>Valor Entregue</th>
								<th>Valor Não Entregue</th>
								<th>Valor em Atraso</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($notasPorRemetente as $cnpj => $notas)
					            <tr>
				                    <td>{{ $cnpj }}</td>
				                    <td>{{ $notas['nome_remete'] }}</td>
				                    <td>R$ {{ $notas['valor_total'] }}</td>
				                    <td>R$ {{ $notas['valor_entregue'] }}</td>
				                    <td>R$ {{ $notas['valor_nao_entregue'] }}</td>
				                    <td>R$ {{ $notas['valor_atraso'] }}</td>
				                </tr>
				            @empty
				            	<tr>
									<td colspan="6">Nenhum registro encontrado</td>
								</tr>
					        @endforelse
						</tbody>
					</table>								
				</div>
			</div>
		</div>
	</main>

	{{-- JS bootstrap --}}
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>